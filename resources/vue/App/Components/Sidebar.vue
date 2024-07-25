<!--
Sidebar.vue is content that would be passed into SidebarRoot.vue via $store
-->
<script>
export default {
    name: 'sidebar',
    props: ['title']
}
</script>

<template>
    <div>
        <h3>{{ title }} <button class="right-sidebar-toggle" v-on:click="$sidebar.hide()"><i class="fas fa-times"></i></button></h3>
        <slot></slot>
    </div>
</template>