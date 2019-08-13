    

1) 导入包的下划线说明 

    _ "github.com/goinaction/code/chapter2/sample/matchers"
    "github.com/goinaction/code/chapter2/sample/search"
    
Go编译器不允许声明导入某个包却不使用，下划线让编译器接受这类导入,并且调用对应包中的 init() 函数

2) init 函数会在 main前调用

3) 包里公开的函数必须大写字母才可以

4) 错误处理

    [https://www.jianshu.com/p/267e746923b1](https://www.jianshu.com/p/267e746923b1)
    
5) go channel 表达

    https://blog.csdn.net/tennysonsky/article/details/79068667
    
6) sync.WaitGroup

    等待goroutines完成
    
7) 获取当前文件名称

    _, filename, _, _ := runtime.Caller(1) 

8) 读取json数据

    # https://studygolang.com/static/pkgdoc/pkg/encoding_json.htm#Decoder.Decode
    json.NewDecoder(file).Decode(&feeds)
    
    Decode从输入流读取下一个json编码值并保存在v指向的值里
    
    读取json数据并且写入到feeds中去
    
9) 生成一个token

        // 得到一个时间错
        crutime := time.Now().Unix()

        // 得到一个md5值
		h := md5.New()

        // 
		io.WriteString(h, strconv.FormatInt(crutime, 10))

		token := fmt.Sprintf("%x", h.Sum(nil))
    




    