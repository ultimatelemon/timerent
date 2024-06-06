export const isApplication = (url) => {
    const host = new URL(url)
    return host.hostname.split('').filter(val => val === '.').length >= 2

}