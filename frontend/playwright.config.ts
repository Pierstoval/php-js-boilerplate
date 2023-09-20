import type { PlaywrightTestConfig } from '@playwright/test';

const config: PlaywrightTestConfig = {
	webServer: {
		command: 'yarn run build && yarn run preview',
		port: 4173
	},
	use: {
		browserName: 'firefox',
		defaultBrowserType: 'firefox',
		headless: true
	},
	testMatch: /tests\/.*.(js|ts)/
};

export default config;
