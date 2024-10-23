<script lang="ts">
	import { onMount } from 'svelte';
	import { getApi } from '$lib/openapi';
	import { type BookJsonld } from '$lib/openapi/model';
	import { page } from '$app/stores';

	let books: Array<BookJsonld> = [];

	onMount(() => {
		getApi()
			.apiBooksGetCollection()
			.then((res) => (books = res.data.member));
	});

	let host = `${$page.url.protocol}//${$page.url.host}/admin`;
</script>

<h1>Available books:</h1>

<table>
	<tbody>
		<tr>
			<th>#</th>
			<th>Title</th>
			<th>Actions</th>
		</tr>
		{#each books as book (book.id)}
			<tr>
				<td>{book.id.substring(0, 8)}{book.id.length > 8 ? '…' : ''}</td>
				<td>{book.title}</td>
				<td>
					<a href="/books/{book.id}">View</a>
				</td>
			</tr>
		{:else}
			<tr>
				<td colspan="3">
					No books yet! Use the admin to add some: <a href={host}>{host}</a>
				</td>
			</tr>
		{/each}
	</tbody>
</table>

<style lang="scss">
	table {
		@apply table-auto min-w-[30rem];
		th,
		td {
			border: solid 1px #aaa;
			padding: 2px 10px;
		}
		tr th:first-child {
			@apply w-1/12;
		}
		tr th:nth-child(1),
		tr th:nth-child(2) {
			@apply w-4/12;
		}
		a {
			color: blue;
			&:hover {
				text-decoration: underline;
			}
		}
	}
</style>
