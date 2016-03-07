chai.should();

describe('yii.validation.js', function() {
    it('should exist', function() {
        yii.validation.should.be.an('Object');
    });

    describe('number validator', function() {
        leche.withData([
            [20],
            [0],
            [-20],
            ['20'],
            [25.45]
        ], function(value) {
            it('should accept valid value', function() {
                var messages = [];
                validators.number(value, messages);
                messages.should.be.empty;
            });
        });
        leche.withData([
            ['25,45'],
            ['12:45']
        ], function(value) {
            it('should reject invalid value', function() {
                var messages = [];
                validators.number(value, messages);
                messages.should.have.lengthOf(1);
                messages[0].should.equal('Attribute must be a number.');
            });
        });
    });
});
