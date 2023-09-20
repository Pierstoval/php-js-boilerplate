import { error } from '@sveltejs/kit';

/** @type {import('./$types').PageLoad} */
export function load({ params }) {
	if (!params.id) {
		throw error(404, 'Not found');
	}

	return {
		id: params.id
	};
}
