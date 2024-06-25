import puppeteer from 'puppeteer';
(async () => {
    const browser = await puppeteer.launch({
        executablePath: '/usr/bin/google-chrome',
        args: [
            '--disable-gpu',
            '--disable-dev-shm-usage',
            '--disable-setuid-sandbox',
            '--no-sandbox'
        ]
    });
    const page = await browser.newPage();
    await page.goto('https://www.thegioididong.com/');

    const MAX_PRODUCT_PER_PAGE = 20;
    const sortTotalElement = await page.$('p.sort-total');
    const sortTotalText = await page.evaluate(element => element.textContent, sortTotalElement);
    const sortTotal = Math.floor(sortTotalText.replace(/\D/g, '') / MAX_PRODUCT_PER_PAGE);

    for (var i = 0; i < sortTotal; i++) {
        let loadMoreButton = await page.$('div.view-more a');
        // console.log(loadMoreButton);
        if (loadMoreButton) {
            await loadMoreButton.click();
            await page.waitForNavigation({ waitUntil: 'networkidle0' });
        } else {
            break;
        }
    }
    console.log(await page.content());
    // const elements = await page.$$('li.item.ajaxed');
    // console.log(elements.length);
    
    await browser.close();
})();
