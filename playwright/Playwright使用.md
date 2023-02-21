## 前置文档

先进行安装

    > pip3 config set global.index-url https://pypi.tuna.tsinghua.edu.cn/simple
    > pip3 install playwright

使用 `playwright -h `看看他支持的功能

![图片描述...](https://cdn.surest.cn/Fi68glRkhD2mKKN7Gigkr0qqCXu4)

## 安装一些谷歌 或者其他浏览器运行环境

运行环境参考:

-   https://playwright.dev/docs/api/class-browser
-   https://playwright.dev/docs/api/class-browsertype

我们这里使用 `chromium` 来进行

    > mkdir playwright
    > cd playwright
    > vi play.js

写入我们的代码

    const { chromium } = require('playwright');  // Or 'firefox' or 'webkit'.

    (async () => {
        const browser = await chromium.launch();
        const page = await browser.newPage();
        await page.goto('https://baidu.com');
        // other actions...
        await browser.close();
    })();

如果报错 `playwright` 不存在，可以全局安装一下 `npm instal -g playwright`

依旧报错后，则进行 检查 `NODE_PATH` 是否设置, 可以尝试 输出 `echo $NODE_PATH`

    > node play.js

会打开一个谷歌浏览器，进入到百度网站中，然后自动关闭页面

## 针对脚本进行录制

尝试使用命令

    > playwright codegen -o record.js --target javascript https://cn.bing.com

![图片描述...](https://cdn.surest.cn/loGnPhovEihc_Naecoqz8XjAAWEY)

我们在其中输入 `playwright是什么`，然后关闭页面

我们会发现，在我们的目录下面生成了一个 record.js 文件

    const { chromium } = require('playwright');

    (async () => {
    const browser = await chromium.launch({
        headless: false
    });
    const context = await browser.newContext();

    // Open new page
    const page = await context.newPage();

    // Go to https://cn.bing.com/
    await page.goto('https://cn.bing.com/');

    // Click input[name="q"]
    await page.click('input[name="q"]');

    // Fill input[name="q"]
    await page.fill('input[name="q"]', 'playwright是什么');

    // Press Enter
    await page.press('input[name="q"]', 'Enter');
    // assert.equal(page.url(), 'https://cn.bing.com/search?q=playwright%E6%98%AF%E4%BB%80%E4%B9%88&form=QBLH&sp=-1&pq=playwright%E6%98%AF%E4%BB%80%E4%B9%88&sc=2-13&qs=n&sk=&cvid=E0E598C6A8424D8AB3E43299CB6FC860&ghsh=0&ghacc=0&ghpl=');

    // Click :nth-match(:text("自动化神器Playwright快速上手指南 - 知乎"), 2)
    const [page1] = await Promise.all([
        page.waitForEvent('popup'),
        page.click(':nth-match(:text("自动化神器Playwright快速上手指南 - 知乎"), 2)')
    ]);

    // Close page
    await page1.close();

    // Close page
    await page.close();

    // ---------------------
    await context.close();
    await browser.close();
    })();

通过如上代码差不多可以看的出来，可以使用 js 对他进行 自动化操作，可以以 dom 为节点

## 使用 docker 进行容器化访问

当我们想让他在后台进行运行的时候，我们可以尝试使用 docker 来运行这个 服务，我们尝试开源项目 https://github.com/browserless/chrome ，具体用法，我们这里不做明细，readme.md 有

当然，我们不想立马使用 docker， 可以采用这个网站进行在线测试

https://chrome.browserless.io/

## 常见的一些 Api 使用

-   浏览器使用指定大小窗口访问

```const context = await browser.newContext();
const page = await context.newPage();
await page.setViewportSize({
    width: 1920,
    height: 1080,
});
```

-   执行 JS 代码

```
   const content = await page.evaluate(() => {
       return document.querySelector(".note-content").innerText;
   });
   console.log("content", content);
```

-   针对网页进行截图

```
    const content = await page.evaluate(() => {
        return document.querySelector(".note-content").innerText;
    });
    console.log("content", content);
```

-   操作记录跟踪

```
    await context.tracing.start({ screenshots: true, snapshots: true });
    // Open new page
    const page = await context.newPage();

    await page.goto("https://www.baidu.com");

    await context.tracing.stop({ path: "trace.zip" });
...

```

-   设置 指定的 谷歌运行目录

```
    const browser = await chromium.launch({
		headless: false,
		executablePath:
			"/Users/surest/Library/Caches/ms-playwright/chromium-920619/chrome-mac/Chromium.app/Contents/MacOS/Chromium",
	});
```

## 引用文档

-   https://playwright.dev/docs/api/class-tracing
-   https://playwright.dev/docs/api/class-consolemessage
-   https://playwright.dev/docs/intro
-   入门

https://zhuanlan.zhihu.com/p/587558755

-   官网文档

https://playwright.dev/docs/intro

-   浏览器操作 Api

https://playwright.dev/docs/api/class-browsertype

-   设置浏览器的一些基本信息

https://playwright.dev/docs/api/class-apirequest#api-request-new-context
