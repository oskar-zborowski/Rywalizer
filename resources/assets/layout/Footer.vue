<style lang="scss" scoped>
@import "@/styles/bootstrap.scss";

.footer {
    height: $topbar-height;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: none;

    @include respond-to(null, "lg") {
        display: none;
    }
}

.links {
    display: flex;
    gap: 20px;
}
</style>

<template>
    <div class="footer">
        <span> Nasza nazwa 2021 </span>
        <span class="links">
            <span>Polityka prywatności</span>
            <span>Regulamin</span>
        </span>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    setup() {},
});
</script>
