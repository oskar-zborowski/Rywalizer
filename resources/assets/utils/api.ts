// export const apiHost = 'https://zimorodek.pl';
export const apiHost = 'http://localhost:81';

/**
 * 
 * @param relativeUrl url relative to api host
 * @returns absolute url
 * @see `apiHost`
 */
export const getApiUrl = (relativeUrl: string, host = apiHost) => {
    return host.replace(/\/$/, '') + '/' + relativeUrl.replace(/^\//, '');
};