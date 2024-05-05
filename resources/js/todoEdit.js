// function dietForm(initialType) {
window.dietForm = function(initialType) {
    console.log("Initial Type:", initialType);
    return {
        typeSelect: initialType,
        preText: '',
        units: '',
        init() {
            this.updateValues();
        },
        updateValues() {
            switch (this.typeSelect) {
                case '1':
                    this.preText = '少于';
                    this.units = '次';
                    break;
                case '2':
                    this.preText = '至少';
                    this.units = '豪升';
                    break;
                case '3':
                    this.preText = '少于';
                    this.units = '次';
                    break;
                case '4':
                    this.preText = '至少';
                    this.units = '份';
                    break;
                default:
                    this.preText = '';
                    this.units = '';
            }
        }
    };
}
// function routineForm(initialType) {
window.routineForm = function(initialType) {
    return {
    typeSelect: initialType,
    preText: '',
    units: '',
    init() {
        this.updateValues();
    },
    updateValues() {
        switch (this.typeSelect) {
            case '1':
                this.preText = '早於';
                this.units = '睡覺';
                break;
            case '2':
                this.preText = '早於';
                this.units = '起床';
                break;
            case '3':
                this.preText = '睡滿';
                this.units = '小時';
                break;
            default:
                this.preText = '';
                this.units = '';
                break;
        }
    }
};
}