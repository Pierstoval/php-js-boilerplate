import { defineConfig } from '@playwright/test';

export default defineConfig({
	webServer: {
		command: 'pnpm run build && npm run preview',
		port: 4173
	},
	use: {
		browserName: 'firefox',
		defaultBrowserType: 'firefox',
		headless: true
	},
	testDir: 'e2e'
});
