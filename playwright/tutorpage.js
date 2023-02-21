const { chromium } = require("playwright");

(async () => {
	const browser = await chromium.launch({
		headless: false,
		executablePath:
			"/Users/surest/Library/Caches/ms-playwright/chromium-920619/chrome-mac/Chromium.app/Contents/MacOS/Chromium",
	});
	const context = await browser.newContext();

	await context.tracing.start({ screenshots: true, snapshots: true });

	// Open new page
	const page = await context.newPage();

	// Go to https://teacher.tutorpage.net/
	await page.goto(
		"https://tutorpage.net/features/online-tutoring-integration"
	);

	await page.goto("https://tutorpage.net/features/scheduling-calendar");

	await page.goto("https://tutorpage.net/features/online-payment-invoice");

	await context.tracing.stop({ path: "trace.zip" });

	// Close page
	await page.close();

	// ---------------------
	await context.close();
	await browser.close();
})();
