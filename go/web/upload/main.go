package main

import (
	"crypto/md5"
	"fmt"
	"html/template"
	"io"
	"log"
	"net/http"
	"os"
	"strconv"
	"time"
)

// 参考 : https://www.cnblogs.com/jkko123/p/7001673.html

func uploadOne(w http.ResponseWriter, r *http.Request) {
	fmt.Println("methods:", r.Method)

	if r.Method == "GET" {
		// 得到一个当前的unix时间戳
		crutime := time.Now().Unix()

		// 创建一个md5值
		h := md5.New()

		// strconv.FormatInt
		// 将整数转换为字符串形式。base 表示转换进制，取值在 2 到 36 之间。
		// 结果中大于 10 的数字用小写字母 a - z 表示。
		// ----
		// 得到的字符串写入到h中，如果 h 已经实现了WriteString则直接返回 h ， md5 未实现
		io.WriteString(h, strconv.FormatInt(crutime, 10))

		// h.Sum 的意思为 h是一个md5值， 通过h.sum 得到一个hash散列值
		token := fmt.Sprintf("%x", h.Sum(nil))

		// 解析模板引擎
		t, _ := template.ParseFiles("uploadOne.html")

		// 输出模板和参数token
		t.Execute(w, token)
	} else {

		// ParseMultipartForm将请求的主体作为multipart/form-data解析。
		// 请求的整个主体都会被解析，得到的文件记录最多maxMemery字节保存在内存，其余部分保存在硬盘的temp文件里
		// 32 << 20 表示 转化为二进制并且向左边偏移20位
		r.ParseMultipartForm(10 << 20) // 10 mb

		file, handler, err := r.FormFile("file")

		if err != nil {
			fmt.Println(err)
			return
		}

		defer file.Close()

		fmt.Fprintf(w, "%v", handler.Header)

		os.Mkdir("./upload", os.ModePerm)

		f, err := os.OpenFile("./upload/"+handler.Filename, os.O_WRONLY|os.O_CREATE, 0666)

		if err != nil {
			fmt.Println(err)
			return
		}

		defer f.Close()

		// 将file文件流写入到f中，f已经在上面创建成了一个文件， 相当于，改变如上的f文件信息为当前的上传的文件信息
		io.Copy(f, file)

		// application/x-www-form-urlencoded 的时候 不需要使用 r.ParseForm()
		r.ParseForm()

		token := r.Form.Get("token")

		if token != "" {
			fmt.Println("echo :%s", token)
		} else {
			fmt.Println("token验证失败")
		}
		//
		//fmt.Println("username length:", len(r.Form["username"][0]))
		//fmt.Println("username:", template.HTMLEscapeString(r.Form.Get("username"))) // 输出到服务器端
		//fmt.Println("password:", template.HTMLEscapeString(r.Form.Get("password")))
		template.HTMLEscape(w, []byte(handler.Filename)) // 输出到客户端
	}
}

func uploadMore(w http.ResponseWriter, r *http.Request)  {

	if r.Method == "POST" {
		r. ParseMultipartForm(30 << 20)

		// 多图获取图片信息
		files  := r.MultipartForm.File["file"]

		len := len(files)

		for i :=0; i < len; i++{
			file, err := files[i].Open()
			defer file.Close()

			checkErr(err)

			os.Mkdir("./upload", os.ModePerm)

			cur, err := os.Create("./upload/" + files[i].Filename)

			defer cur.Close()

			io.Copy(cur, file)

			r.ParseForm()

			fmt.Println("name", template.HTMLEscapeString(r.Form.Get("name")) )

			template.HTMLEscape(w, []byte(r.Form.Get("name")))
		}

	} else {
		t, _ := template.ParseFiles("./uploadMore.html")

		t.Execute(w, nil)
	}
}


func checkErr(err error)  {
	if err != nil {
		log.Fatal(err)
	}
}

func main() {
	http.HandleFunc("/upload", uploadOne)
	http.HandleFunc("/uploadMore", uploadMore)
	http.ListenAndServe(":8081", nil)
}
