package main

import (
	"fmt"
	"net/url"
	"os"
	"os/exec"
	"path/filepath"
	"strings"
)

func main()  {
	s := "https://blog.csdn.net/wangshubo1989/article/details/74330674"

	u, _ := url.Parse(s)

	s = u.Path

	// 将 path 中的 ‘/’ 转换为系统相关的路径分隔符
	s2 := filepath.FromSlash(s)

	// 将 path 中平台相关的路径分隔符转换为 ‘/’
	s3 := filepath.ToSlash(s)

	// 获取 path 中最后一个分隔符之前的部分（不包含分隔符）
	d := filepath.Dir(s)

	// 获取 path 中最后一个分隔符之后的部分（不包含分隔符）
	b := filepath.Base(s)

	// 获取 path 中最后一个分隔符前后的两部分,之前包含分隔符，之后不包含分隔符
	dir, f := filepath.Split(s)

	// 获取文件后缀名
	ext := filepath.Ext("1.jpg")

	// 获取 targpath 相对于 basepath 的路径。
	s4, err := filepath.Rel(`/a/b/c`, `/a/b/c/d/e`)


	fmt.Println(s2)
	fmt.Println(s3)
	fmt.Println(s[1:])
	fmt.Println(d)
	fmt.Println(b)
	fmt.Println(dir, f)
	fmt.Println(ext[1:])
	fmt.Println(s4, err)
	// 将 elem 中的多个元素合并为一个路径，忽略空元素，清理多余字符。
	fmt.Println(getCurrentPath())
	fmt.Println(filepath.Abs(filepath.Dir(os.Args[0])))

	file , err := exec.LookPath(os.Args[0])
	fmt.Println(file)

	// 获取绝对路径
	fmt.Println(filepath.Abs(`/a/b/c`))

	//dirs_handler(s)
	// 结果
	//\wangshubo1989\article\details\74330674
	///wangshubo1989/article/details/74330674
	//wangshubo1989/article/details/74330674
	//\wangshubo1989\article\details
	//74330674
	///wangshubo1989/article/details/ 74330674
	//jpg
	// 执行的时候切记， 是在file 目录下 使用 go run main.go 执行的话， 则在file下创建目录
}

func dirs_handler(s string) {
	s = "./" + s[1:]
	fmt.Println(s)
	// 递归创建目录
	if err := os.MkdirAll(s, 0777); err != nil {
		fmt.Println(err)
	}
	fmt.Println("文件目录创建成功")
}

func getCurrentPath() string {
	s, err := exec.LookPath(os.Args[0])
	checkErr(err)
	i := strings.LastIndex(s, "\\")
	path := string(s[0 : i+1])
	return path
}

func checkErr(err error) {
	if err != nil {
		panic(err)
	}
}
