package main

import (
	"crypto/md5"
	"fmt"
	"html/template"
	"io"
	"net/http"
	"os"
	"strconv"
	"time"
)

func upload(w http.ResponseWriter, r *http.Request) {
	fmt.Println("methods:", r.Method)

	if r.Method == "GET" {
		crutime := time.Now().Unix()

		h := md5.New()

		io.WriteString(h, strconv.FormatInt(crutime, 10))

		token := fmt.Sprintf("%x", h.Sum(nil))

		t, _ := template.ParseFiles("upload.gtpl")

		t.Execute(w, token)
	} else {
		r.ParseMultipartForm(32 << 20)

		file, handler, err := r.FormFile("uploadFile")

		if err != nil {
			fmt.Println(err)
			return
		}

		defer file.Close()

		fmt.Fprintf(w, "%v", handler.Header)

		f, err := os.OpenFile("./test/"+handler.Filename, os.O_WRONLY|os.O_CREATE, 0666)

		if err != nil {
			fmt.Println(err)
			return
		}

		defer f.Close()

		io.Copy(f, file)
		// application/x-www-form-urlencoded 的时候 不需要使用 r.ParseForm()
		r.ParseForm()

		token := r.Form.Get("token")

		if token != "" {
			fmt.Println("echo :%s", token)
		} else {
			fmt.Println("token验证失败")
		}

		fmt.Println("username length:", len(r.Form["username"][0]))
		fmt.Println("username:", template.HTMLEscapeString(r.Form.Get("username"))) // 输出到服务器端
		fmt.Println("password:", template.HTMLEscapeString(r.Form.Get("password")))
		template.HTMLEscape(w, []byte(r.Form.Get("username"))) // 输出到客户端
	}
}

func main() {
	http.HandleFunc("/upload", upload)
	http.ListenAndServe(":8081", nil)
}
