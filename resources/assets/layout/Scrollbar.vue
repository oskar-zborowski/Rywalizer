<style lang="scss" scoped>
@import "@/styles/bootstrap.scss";

.scrollbar {
    @include respond-to(null, "lg") {
        display: none;
    }

    margin: 0 40px;
    border-radius: 4px;
    border: 1px solid #29292e;
    width: 12px;
    background-color: #18181c;
    flex: none;
    position: relative;
    overflow: hidden;
    .thumb {
        background-color: orange;
        border-radius: 3px;
        width: 100%;
        position: absolute;
    }
}
</style>

<template>
    <div class="scrollbar" ref="scrollbar">
        <div class="thumb" :style="thumbStyle"></div>
    </div>
</template>

<script lang="ts">
import { setContainerEvent } from "@/hooks/useScrollbar";
import { defineComponent, onMounted, onUnmounted, ref, inject, Ref } from "vue";

interface IThumbStyle {
    top?: string;
    height?: string;
}

export default defineComponent({
    name: "Scrollbar",
    components: {},
    setup() {
        const scrollbar = ref<HTMLElement | null>(null);
        const thumbStyle = ref<IThumbStyle>({});
        let container: Ref<HTMLElement>;

        // https://stackoverflow.com/questions/66537320/vue-3-event-bus-with-composition-api
        const emitter = inject<any>("emitter");

        emitter.on(setContainerEvent, (containerRef: Ref<HTMLElement>) => {
            if (container) {
                container.value.removeEventListener("scroll", updateStyles);
            }

            container = containerRef;
            container.value.addEventListener("scroll", updateStyles);
            updateStyles();
        });

        const updateStyles = () => {
            const scrollbarHeight = scrollbar.value?.offsetHeight;
            const containerHeight = container?.value?.offsetHeight;
            const scrollHeight = container?.value?.scrollHeight;
            const scrollTop = container?.value?.scrollTop;

            // console.log(
            //     scrollbarHeight,
            //     containerHeight,
            //     scrollHeight,
            //     scrollTop
            // );

            if (
                scrollbarHeight != undefined &&
                containerHeight != undefined &&
                scrollHeight != undefined &&
                scrollTop != undefined
            ) {
                if (scrollHeight <= containerHeight) {
                    thumbStyle.value = {
                        height: "0px",
                        top: "0%",
                    };
                } else {
                    thumbStyle.value = {
                        height:
                            (containerHeight * scrollbarHeight) / scrollHeight +
                            "px",
                        top: (scrollTop / scrollHeight) * 100 + "%",
                    };
                }
            }
        };

        onMounted(() => {
            window.addEventListener("resize", updateStyles);
        });

        onUnmounted(() => {
            window.removeEventListener("resize", updateStyles);
        });

        return {
            scrollbar,
            thumbStyle,
        };
    },
});
</script>