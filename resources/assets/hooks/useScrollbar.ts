import { inject, Ref } from "vue";

const useScrollbar = () => {
    const emitter = inject<any>("emitter");

    return {
        setContainer: <T>(container: Ref<T>) => {
            emitter.emit(setContainerEvent, container);
        },
    };
};

export default useScrollbar;

export const setContainerEvent = 'SCROLLBAR:SET_CONTAINER';