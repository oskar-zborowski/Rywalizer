<style lang="scss" scoped>
.content {
    flex: 1;
    display: flex;
    flex-direction: row;
    min-height: 0;
}
</style>

<template>
    <div class="content">
        <slot/>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    setup() {},
});
</script>
