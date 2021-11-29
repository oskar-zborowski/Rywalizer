<style lang="scss" scoped>
@import "@/styles/bootstrap.scss";

.topbar {
    width: 100%;
    height: $topbar-height;
    display: flex;
    align-items: center;
    flex-direction: row;
    justify-content: space-between;
    flex: none;
}

.logo {
    color: orange;
    font-size: 30px;
    font-weight: 1000;
}

.links {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 20px;

    @include respond-to(null, "lg") {
        display: none;
    }
}
</style>

<template>
    <div class="topbar">
        <div class="logo">LOGO</div>
        <nav class="links">
            <span>Rezerwacje</span>
            <span>Wydarzenia</span>
            <span>Współpraca</span>
        </nav>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    setup() {},
});
</script>
