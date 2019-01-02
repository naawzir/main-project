export function normalise(postcode) {
    return postcode.toUpperCase().replace(/\s+/g, ' ');
}

export default {
    normalise,
};
