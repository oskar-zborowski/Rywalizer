export const scrollToElement = (containerRef: React.RefObject<HTMLElement>, elementId: string, offset = 0) => {
    const element = document.getElementById(elementId);

    const elementTop = element.getBoundingClientRect().top;
    const containerTop = containerRef.current?.getBoundingClientRect().top;

    const elementPosition = elementTop - containerTop;
    const offsetPosition = elementPosition - offset;

    containerRef.current?.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
};