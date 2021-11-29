<style lang="scss" scoped>
@import '@/styles/bootstrap.scss';

.mapViewer {
    @extend %right-column;

    @include respond-to(null, 'lg') {
        display: none;
    }

    border-radius: 15px;
    overflow: hidden;
    flex: auto;
    border: 1px solid #29292e;
    background-color: #29292e;
}
</style>

<template>
    <div class=mapViewer>
        <GoogleMap
            api-key="AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y"
            style="width: 100%; height: 100%"
            :center="center"
            :zoom="6"
        >
        </GoogleMap>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { GoogleMap, Marker } from "vue3-google-map";

export default defineComponent({
    components: { GoogleMap, Marker },
    setup() {
        const center = { lat: 52, lng: 20 };

        return { center };
    },
});
</script>