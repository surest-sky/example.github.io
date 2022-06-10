const CreateMessage = (target, data) => {
    return {
        target,
        data
    }
}

class IframeMessage {
    constructor(iframeWindow, allowOrigin = "*") {
        this.iframe = iframeWindow
        this.allowOrigin = allowOrigin
        this.bindHandler = []
    }

    // 注册监听函数
    registerHandler(handler) {
        this.handler = handler
        const that = this
        window.addEventListener("message", (event) => {
            this.receiveMessage(event, that)
        }, false);
    }

    // 消息接受
    receiveMessage(event, that) {
        if (that.allowOrigin !== "*" && event.origin !== that.allowOrigin) {
            throw new Error("拒绝未知源数据")
            return false
        }
        const { data, origin, lastEventId, source, ports } = event
        that.handler && that.handler(data)
        that.dispatch(data, that)
    }

    // 发送消息
    sendMessage(message) {
        this.iframe.postMessage(message, this.allowOrigin)
    }

    // 数据分发
    dispatch(message, that) {
        const { target, data } = message
        const handler = this.bindHandler[target]
        if (!handler) {
            return false
        }
        handler && handler(data)
    }

    // 页面成功事件
    success(handler) {
        console.log("iframe.js:success", handler)
        this.bindHandler['success'] = handler;
    }

    // 页面失败事件
    error(handler) {
        this.bindHandler['error'] = handler;
    }

    // 页面回调监听事件
    listen(handler) {
        this.bindHandler['listen'] = handler;
    }
}