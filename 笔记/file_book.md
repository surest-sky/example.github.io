个人支付

- https://demo.payjs.cn/
- https://github.com/yioMe/node_wx_alipay_personalPay
- http://xpay.exrick.cn/

upstream myTest {
    server 192.168.2.188:9501;
}


location / {
        proxy_pass http://myTest;   #调用upstream里设定的变量
        proxy_buffering off;          #缓存开关
        
        #以下三行是获得前端IP，反向代理设置。
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
}