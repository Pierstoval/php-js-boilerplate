import { defineConfig } from 'vitest/config';
import { sveltekit } from '@sveltejs/kit/vite';
import { configDefaults } from 'vitest/config';
import { svelteTesting } from '@testing-library/svelte/vite';

export default defineConfig({
	plugins: [sveltekit(), svelteTesting()],

	css: {
		preprocessorOptions: {
			scss: {
				api: 'modern'
			}
		}
	},

	test: {
		include: ['src/**/*.{test,spec}.{js,ts}'],
		exclude: [...configDefaults.exclude, '**/build/**', '**/.svelte-kit/**', '**/dist/**'],
		globals: true,
		environment: 'jsdom',
	}
});
