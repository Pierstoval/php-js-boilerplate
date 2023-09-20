import { defineConfig } from 'orval';

export default defineConfig({
	applicationApi: {
		input: './build/openapi.json',
		output: {
			mode: 'split',
			clean: true,
			prettier: true,
			tslint: true,
			target: './src/lib/openapi/index.ts',
			schemas: 'src/lib/openapi/model/',
			client: 'axios' // This is the default.
		},
		hooks: {
			afterAllFilesWrite: 'prettier --write'
		}
	}
});
