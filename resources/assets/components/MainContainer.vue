<style lang="scss" scoped>
@import "@/styles/bootstrap.scss";

.main-container {
    @extend %left-column;

    position: relative;
    border-radius: 15px;
    height: 100%;
    background-color: #18181c;
    border: 1px solid #29292e;
    overflow: auto;

    .auth-buttons {
        position: absolute;
        right: 0;
        top: -$topbar-height / 2;
        transform: translateY(-50%);

        * {
            width: 120px;
            margin-left: 8px;
            float: left;
        }
    }

    @include respond-to(null, "sm") {
        background-color: transparent;
        border-radius: 0;
        border: none;
    }
}
</style>

<template>
    <main class="main-container" ref="container">
        <div className="auth-buttons"></div>
        <div style="height: 4000px"></div>
        <slot/>
    </main>
    <Scrollbar />
</template>

<script lang="ts">
import useScrollbar from "@/hooks/useScrollbar";
import { defineComponent, onMounted, ref } from "vue";
import Scrollbar from "../layout/Scrollbar.vue";

export default defineComponent({
    components: { Scrollbar },
    setup() {
        const container = ref<HTMLElement>();
        const { setContainer } = useScrollbar();

        onMounted(() => {
            container && setContainer(container);
        });

        return {
            container,
        };
    },
});
</script>
