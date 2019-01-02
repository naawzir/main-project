import { assert } from 'chai';
import { normalise } from '../../../resources/js/utils/postcodes';

describe('In the postcodes module', () => {
    describe('the normalise function', function () {
        it('should convert the postcode to upper case', function () {
            assert.equal('NE3 3LU', normalise('ne3 3lu'));
        });
        it('should remove duplicate spaces', function () {
            assert.equal('NE3 3LU', normalise('ne3  3lu'));
        });
        it('should throw an error if the argument is not a string', function () {
            assert.throws(() => normalise(undefined));
        });
    });
});
