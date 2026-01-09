<script setup lang="ts">
import { onBeforeMount, onMounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router'
import { useUserStore } from './stores/userStore';

const userStore = useUserStore()
onBeforeMount(async () =>{
  await userStore.getUser();
})
</script>

<template>
  <RouterView />
</template>

