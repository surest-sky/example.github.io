const { chromium } = require("playwright");

(async () => {
	const browser = await chromium.launch({
		headless: false,
		executablePath:
			"/Users/surest/Library/Caches/ms-playwright/chromium-920619/chrome-mac/Chromium.app/Contents/MacOS/Chromium",
	});
	const context = await browser.newContext();
	const page = await context.newPage();
	await page.goto(
		"https://www.xiaohongshu.com/explore/63d49744000000001a02619c"
	);

	await new Promise((resolve) => {
		setTimeout(() => {
			resolve(true);
		}, 2000);
	});
	const content = await page.evaluate(() => {
		return document.querySelector(".note-content").innerText;
	});

	console.log("content", content);

	// Can pause by injecting a "debugger;" statement
	await page.evaluate(() => {
		debugger;
	});

	console.log(content);

	// ---------------------
	await context.close();
	await browser.close();
})();
