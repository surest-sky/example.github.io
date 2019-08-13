package main

import (
	"fmt"
	"html/template"
	"log"
	"net/http"
	"net/url"
	"strings"
)

func sayhelloName(w http.ResponseWriter, r *http.Request) {
	r.ParseForm()

	fmt.Println(r.Form)
	fmt.Println("path", r.URL.Path)
	fmt.Println("scheme", r.URL.Scheme)
	fmt.Println(r.Form["url_long"])
	for k, v := range r.Form {
		fmt.Println("key: ", k)
		fmt.Println("val: ", strings.Join(v, ""))
	}
	fmt.Fprintf(w, "hello astaxie!")
}

func login(w http.ResponseWriter, r *http.Request) {
	fmt.Println("method: ", r.Method)

	if r.Method == "GET" {
		t, _ := template.ParseFiles("login.gtpl")
		log.Println(t.Execute(w, nil))
	} else {
		err := r.ParseForm()

		if err != nil {
			log.Fatal("parseForm: ", err)
		}

		v := url.Values{}
		v.Set("name", "Ava")
		v.Add("friend", "Jess")

		fmt.Println(v.Get("name"))
		fmt.Println(v.Get("friend"))
		fmt.Println(v)

		// 服务器端
		fmt.Println("username - ", template.HTMLEscapeString(r.Form.Get("username")))
		fmt.Println("password - ", template.HTMLEscapeString(r.Form.Get("password")))

		// 客户端
		template.HTMLEscape(w, []byte(r.Form.Get(("username"))))

		// 输出html标签
		t, err := template.New("foo").Parse(`{{define "T"}} Hello, {{.}}!{{end}}`)
		err = t.ExecuteTemplate(w, "T", "<script>alert('you have been pwned')</script>")

		fmt.Println("username: ", r.Form["username"])
		fmt.Println("password: ", r.Form["password"])
	}
}

func main() {
	http.HandleFunc("/", sayhelloName)
	http.HandleFunc("/login", login)

	err := http.ListenAndServe(":9090", nil)

	if err != nil {
		log.Fatal("ListenAdnServe: ", err)
	}
}
