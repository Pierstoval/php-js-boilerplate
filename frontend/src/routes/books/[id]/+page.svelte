<script lang="ts">
	import {onMount} from "svelte";
	import {page} from '$app/stores';
	import {get} from "$lib/openapi";
	import {type Book} from "$lib/openapi/model";

	let id = $page.params['id'];

	let book: Book|null = null;

    let finished_loading = false;

	onMount(() => {
		if (!id) return;
		get().apiBooksIdGet(id).then(res => book = res.data);

        finished_loading = true;
	});
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

