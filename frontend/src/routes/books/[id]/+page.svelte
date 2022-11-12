<script lang="ts">
	import {onMount} from "svelte";
	import {get} from "$lib/openapi";
	import {type Book} from "$lib/openapi/model";

	/** @type {import('./$types').PageData} */
	export let data;

	let book: Book|null = null;

	onMount(() => {
		if (!data.id) {
			return;
		}
		get().apiBooksIdGet(data.id).then(res => book = res.data);
	});
</script>

{#if !book}
	...
{:else}
	<h1>Book {book.title}</h1>

	<span>Id: {book.id}</span>
{/if}
