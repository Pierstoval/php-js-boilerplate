import { describe, it, expect } from 'vitest';
import { render } from '@testing-library/svelte';
import '@testing-library/jest-dom';
import ComponentToTest from './TopMenu.svelte';

describe('TopMenu component', () => {
	it('can be instantiated', async () => {
		const rendered = render(ComponentToTest);

		// const element = rendered.container;
		// expect(element).toBeDefined();
		//
		// const link = rendered.container.querySelector('li a');
		// expect(link).toBeDefined();
		// expect(link.innerText).toStrictEqual('Home');
	});
});
