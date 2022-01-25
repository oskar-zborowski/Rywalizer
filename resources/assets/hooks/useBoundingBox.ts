import { useEffect, useState } from 'react';

const useBoundingBox = (ref: React.RefObject<HTMLElement>) => {
    const [bbox, setBbox] = useState<DOMRect>();
    useEffect(() => {
        const handleResize = () => {
            const bbox = ref.current.getBoundingClientRect();
            setBbox(bbox);
        };

        handleResize();
        window.addEventListener('resize', handleResize);

        return () => {
            window.removeEventListener('resize', handleResize);
        };
    }, [ref]);

    return bbox;
};

export default useBoundingBox;