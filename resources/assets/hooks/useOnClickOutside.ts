import { useEffect } from 'react';

export const useOnClickOutside = (
    refs: React.RefObject<any>[], 
    onClickOutside: (event: MouseEvent) => void,
    onClickInside?: (event: MouseEvent) => void
) => {
    useEffect(() => {
        const listener = (event: MouseEvent) => {
            for(const ref of refs) {
                if (ref.current?.contains(event.target)) {
                    onClickInside?.(event);
                    return;
                }
            }

            onClickOutside(event);
        };
  
        document.addEventListener('mousedown', listener);
        document.addEventListener('touchstart', listener);
  
        return () => {
            document.removeEventListener('mousedown', listener);
            document.removeEventListener('touchstart', listener);
        };
    }, [...refs, onClickOutside]);
};