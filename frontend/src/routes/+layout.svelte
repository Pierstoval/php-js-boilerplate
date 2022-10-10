<script lang="ts">
	import TopMenu from '../lib/components/TopMenu.svelte';
</script>

<TopMenu/>

<div id="content">
	<slot></slot>
</div>

<style lang="scss" global>
	@import '../styles/app.scss';
</style>
