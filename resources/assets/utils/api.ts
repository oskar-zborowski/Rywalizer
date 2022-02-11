export const apiHost = 'http://localhost:81';
// export const apiHost = 'http://rywalizer.test';

/**
 * 
 * @param relativeUrl url relative to api host
 * @returns absolute url
 * @see `apiHost`
 */
export const getApiUrl = (relativeUrl: string, host = apiHost) => {
    return host.replace(/\/$/, '') + '/' + relativeUrl.replace(/^\//, '');
};