import { assert } from 'chai';
import { Generator } from '../../../resources/js/utils/id-generator';

describe('ID Generator', () => {
    // Create a mock source
    const source = new (class {
        generate() {
            return this.number;
        }

        setNumber(number) {
            this.number = number;
        }
    })();
    const generator = new Generator(source);

    it('Converts a number into an ID string', () => {
        source.setNumber(0.12345);

        const id = generator.generate();

        assert.isString(id);
        assert.equal('3ud6mk5gu9u', id);
    });

    describe('Throws an error if the number is out of bounds', function () {
        const outOfBounds = [0, 1, -1, 1.1, -1.1];

        for (const i of outOfBounds) {
            it(`with value: ${i}`, () => {
                source.setNumber(i);
                assert.throws(function () {
                    generator.generate();
                });
            });
        }
    })
});
