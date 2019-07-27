package main

import (
	"fmt"
	"time"

	// "time"
	"github.com/astaxie/beego/orm"
	_ "github.com/go-sql-driver/mysql"
)

func init()  {
	orm.RegisterDriver("mysql", orm.DRMySQL)

	orm.RegisterDataBase("default", "mysql", "root:root@/project?charset=utf8", 30)

	orm.RegisterModel(new(User))

	orm.RunSyncdb("default", false, true)
}

type Userinfo struct {
	Uid         int `orm:"PK"` //如果表的主键不是id，那么需要加上pk注释，显式的说这个字段是主键
	Username    string
	Departname  string
	Created     time.Time
}

type User struct {
	Uid         int `orm:"PK"` //如果表的主键不是id，那么需要加上pk注释，显式的说这个字段是主键
	Name        string
	//Profile     *Profile   `orm:"rel(one)"` // OneToOne relation
	//Post        []*Post `orm:"reverse(many)"` // 设置一对多的反向关系
}

type Profile struct {
	Id          int
	Age         int16
	User        *User   `orm:"reverse(one)"` // 设置一对一反向关系(可选)
}

type Post struct {
	Id    int
	Title string
	User  *User  `orm:"rel(fk)"`    // 设置一对多关系
	Tags  []*Tag `orm:"rel(m2m)"`
}

type Tag struct {
	Id    int
	Name  string
	Posts []*Post `orm:"reverse(many)"`
}

func init() {
	// 需要在 init 中注册定义的 model
	orm.RegisterModel(new(Userinfo),new(User), new(Profile), new(Tag))
}

func main() {
	o := orm.NewOrm()
	var user Userinfo
	user.Username = "zxxx"
	user.Departname = "zxxx"

	id, err := o.Insert(&user)
	if err == nil {
		fmt.Println(id)
	}

}
func checkErr(err error) {
	if err != nil {
		panic(err)
	}
}