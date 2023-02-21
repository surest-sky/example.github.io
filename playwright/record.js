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