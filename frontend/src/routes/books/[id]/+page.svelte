<script lang="ts">
	import { onMount } from 'svelte';
    import { page } from '$app/state';
	import { getApi } from '$lib/openapi';
	import { type BookJsonld } from '$lib/openapi/model';

	let book: BookJsonld | null = null;

	let finished_loading = false;

	onMount(() => {
		if (!id) return;

		getApi()
			.apiBooksIdGet(id)
			.then((res) => (book = res.data))
			.finally(() => (finished_loading = true));
	});

    $: id = page.params['id'];
</script>

{#if book === null}
	{#if finished_loading}
		No book found...
	{:else}
		Loading...
	{/if}
{:else}
	<h1>Book: <strong>{book.title}</strong></h1>

	<span>Id: <small>{book.id}</small></span>
{/if}
