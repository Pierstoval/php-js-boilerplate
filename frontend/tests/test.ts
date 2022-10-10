import { expect, test } from '@playwright/test';

test('index page has expected nav link', async ({ page }) => {
	await page.goto('/');
	expect(await page.textContent('nav a')).toBe('Home');
});
