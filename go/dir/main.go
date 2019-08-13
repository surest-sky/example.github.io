package main

import (
	"fmt"
	"io/ioutil"
	"log"
	"os"
	"path/filepath"
)

// https://studygolang.com/articles/11101?fr=sidebar

func main()  {
	folder := `e:\xampp\htdocs\example`

	listFile(folder)

	getCurrentFile()
}

// 递归输出文件目录
func listFile(folder string)  {
	files , _ := ioutil.ReadDir(folder)

	for _, file := range files {
		if file.IsDir() {
			listFile(file.Name())
		} else {
			fmt.Println(folder + "/" + file.Name())
		}
	}
}

func getCurrentFile()  {
	dir, err := filepath.Abs(filepath.Dir(os.Args[0]))

	if err != nil {
		log.Fatal(err)
	}

	println("当前目录 :" + dir)
}
