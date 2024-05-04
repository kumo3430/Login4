function dietForm(initialType) {
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
                  this.preText = '少於';
                  this.units = '次';
                  break;
              case '2':
                  this.preText = '至少';
                  this.units = '豪升';
                  break;
              // Add more cases as needed
              default:
                  this.preText = '';
                  this.units = '';
          }
      }
  };
}