/**
 * Generates numbers to convert into an IDs
 *
 * @interface Source
 */
/**
 * Generate a number to convert into an ID
 *
 * @name Source#generate
 * @returns {number}
 */

/**
 * @implements {Source}
 */
class RandomSource {
    generate() {
        return Math.random();
    }
}

export class Generator {
    /**
     * @param {Source} source
     */
    constructor(source = new RandomSource()) {
        this.source = source;
    }

    /**
     * @returns {string}
     */
    generate() {
        const number = this.source.generate();
        if (number <= 0 || number >= 1) {
            throw new Error('Invalid source number: must be between 0 and 1');
        }

        return number
            .toString(32)
            .slice(2);
    }
}

export default {
    Generator,
};
