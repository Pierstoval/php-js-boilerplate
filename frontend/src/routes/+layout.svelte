<script lang="ts">
	import '../styles/app.scss';

	import TopMenu from '../lib/components/TopMenu.svelte';
</script>

<main>
	<TopMenu/>

	<slot></slot>
</main>

<style lang="scss">
    main {
      @apply container mx-auto;
    }
</style>
