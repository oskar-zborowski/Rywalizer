import useResize from '@/hooks/useResize';
import React, { createContext, useCallback, useContext, useEffect, useRef, useState } from 'react';
import styles from './Scrollbar.scss';

export interface IScrollbarContext {
    containerRef?: React.MutableRefObject<any>;
    scrollbarRef?: React.MutableRefObject<any>;
    thumbLength?: number;
    thumbPosition?: number;
    updateScroll?: () => void;
}

export const ScrollbarContext = createContext<IScrollbarContext>({});

export const ScrollbarProvider: React.FC = (props) => {
    const containerRef = useRef<HTMLElement>(null);
    const scrollbarRef = useRef<HTMLDivElement>(null);

    //TODO: https://stackoverflow.com/questions/55838351/how-do-we-know-when-a-react-ref-current-value-has-changed

    const [thumbLength, setThumbLength] = useState(0);
    const [thumbPosition, setThumbPosition] = useState(0);

    const scrollbarHeight = useResize(scrollbarRef).height;
    const containerHeight = useResize(containerRef as React.MutableRefObject<any>).height;

    const updateScroll = () => {
        const scrollHeight = containerRef?.current?.scrollHeight;
        const scrollTop = containerRef?.current?.scrollTop;

        if (scrollHeight && scrollTop) {
            setThumbLength(containerHeight * scrollbarHeight / scrollHeight);
            setThumbPosition(scrollTop / scrollHeight * 100);
        }
    };

    useEffect(() => {
        if (containerRef && containerRef.current) {
            updateScroll();

            containerRef.current.addEventListener('scroll', updateScroll, false);

            return () => {
                containerRef?.current?.removeEventListener('scroll', updateScroll, false);
            };
        }
    }, [containerRef, scrollbarHeight, containerHeight]);

    const context = { containerRef, scrollbarRef, thumbLength, thumbPosition, updateScroll };

    return (
        <ScrollbarContext.Provider value={context}>
            {props.children}
        </ScrollbarContext.Provider>
    );
};

export const Scrollbar = () => {
    const { scrollbarRef, thumbLength, thumbPosition } = useContext(ScrollbarContext);

    return (
        <div className={styles.scrollbar} ref={scrollbarRef}>
            <div className={styles.thumb} style={{
                height: thumbLength + 'px',
                top: thumbPosition + '%'
            }}></div>
        </div>
    );
};

const useScrollbar = () => {
    const context = useContext(ScrollbarContext);

    useEffect(() => {
        (context.updateScroll as () => void)();
    }, []);

    return context;
};

export default useScrollbar;