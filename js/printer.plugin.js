(function() {

  OCA.Printer = OCA.Printer || {};

  /**
   * @namespace
   */
  OCA.Printer.Util = {

    /**
     * Initialize the Printer plugin.
     *
     * @param {OCA.Files.FileList} fileList file list to be extended
     */
    attach: function(fileList) {

      if (fileList.id === 'trashbin' || fileList.id === 'files.public') {
        return;
      }

      fileList.registerTabView(new OCA.Printer.PrinterTabView('printerTabView', {}));

    }
  };
})();

OC.Plugins.register('OCA.Files.FileList', OCA.Printer.Util);
