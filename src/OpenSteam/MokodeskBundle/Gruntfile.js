var bower = require('bower');

/*global module:false*/
module.exports = function(grunt) {
  grunt.registerTask('bower', 'Install Bower packages.', function () {
    var done = this.async();

    bower.commands.install()
      .on('log', function (result) {
        grunt.log.ok('bower: ' + result.id + ' ' + result.data.endpoint.name);
      })
      .on('error', grunt.fail.warn.bind(grunt.fail))
      .on('end', done);
  });


  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    shortName: 'mokodesk',
    dirs: {
        components: 'Resources/components/',
        componentsGit: 'Resources/components-git/',
        lib: 'Resources/lib/',
        src: 'Resources/src/',
        public: 'Resources/public/',
        views: 'Resources/views/Explorer/'
    },
    ext_js_files: [
        '<%= dirs.lib %>js/ext2/adapter/ext/ext-base.js',
        '<%= dirs.lib %>js/ext2/ext-all.js'
    ],
    lang_de_colloquial_js_files: [
        '<%= dirs.src %>js/mokodesk/moko_lang/ext-lang-de.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/lars_de.js'
    ],
    lang_de_formal_js_files: [
        '<%= dirs.src %>js/mokodesk/moko_lang/ext-lang-de.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/lars_de.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/moko_de.js',
    ],
    lang_en_colloquial_js_files: [
        '<%= dirs.src %>js/mokodesk/moko_lang/ext-lang-en.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/lars_en.js',
    ],
    lang_en_formal_js_files: [
        '<%= dirs.src %>js/mokodesk/moko_lang/ext-lang-en.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/lars_en.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/moko_en.js',
    ],
    lang_fr_js_files: [
        '<%= dirs.src %>js/mokodesk/moko_lang/ext-lang-fr.js',
        '<%= dirs.src %>js/mokodesk/moko_lang/lars_fr.js',
    ],
    ux_js_files: [
        '<%= dirs.lib %>js/ux/Ext.ux.TinyMCE.js',
        '<%= dirs.lib %>js/ux/Ext.ux.ToastLars.js',
        '<%= dirs.lib %>js/ux/Ext.ux.ToastLarsDiscussion.js',
        '<%= dirs.lib %>js/ux/miframe.js',
        '<%= dirs.lib %>js/ux/TabCloseMenu.js',
        '<%= dirs.lib %>js/ux/uxmedia.js',
        '<%= dirs.lib %>js/ux/uxflash.js',
    ],
    add_js_files: [
        '<%= dirs.lib %>js/ASCIIMathML2wMnGFallback.js',
        '<%= dirs.lib %>js/copy.js',
    ],
    menu_js_files: [
        '<%= dirs.lib %>js/menu/EditableItem.js',
        '<%= dirs.lib %>js/menu/RangeMenu.js',
    ],
    grid_js_files: [
        '<%= dirs.lib %>js/grid/Ext.Grid.CheckColumn.js',
        '<%= dirs.lib %>js/grid/Ext.ux.grid.RowActions.js',
        '<%= dirs.lib %>js/grid/Ext.ux.grid.Search.js',
        '<%= dirs.lib %>js/grid/RowExpander.js',
    ],
    moko_js_files: [
        '<%= dirs.src %>js/mokodesk/moko/LarsViewer.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsAddAssignmentWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsAddFolderLinksWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsAddFolderWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsAddLinkWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsAddSchuelerWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsBrowseFileWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsChangeDescWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsCommentWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsCustomImagePanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsCustomPanelTextChange.js',
        //'<%= dirs.src %>js/mokodesk/moko/LarsCustomSite.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDesktop.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDesktopDiscussion.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDesktopErrorWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDesktopGrid.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDesktopNorth3.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDesktopNotes.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDocumentsSubscriptionGrid.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsDocumentsSubscriptionWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsAddUserWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsDesktopsGrid.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsDesktopsGridWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsRightsGrid.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsRightsGridWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsTreePanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsGroupsTreePanelWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsHtmlMessageWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsHtmlPanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsIFramePanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsIFramePanelInternet.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsJoinChat.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsMainPanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsMessageWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsNewHtmlTextWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsNewWebarenaWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsNewHtmlTextWindowNew.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsOverrides.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsPackageDiscussion.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsPackageGrid.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsPackagePanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsResourcesAddNameWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsResourcesAddWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsResourcesPanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsSetPackageRightsWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTopicsPanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTopicsPanelTeacher.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanel.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelArchiv.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelArchivWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelBin.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelBinWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelDesk.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelDeskOthers.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelFolderLinks.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsTreePanelLinks.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsUpdater.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsUploadFileWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsUploadImageWindow.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsVersion.js',
        '<%= dirs.src %>js/mokodesk/moko/LarsVoiceChat.js',
    ],
    moko_css_files: [
        '<%= dirs.src %>js/mokodesk/moko_css/Ext.ux.grid.RowActions.css',
        '<%= dirs.src %>js/mokodesk/moko_css/filetype.css',
        '<%= dirs.src %>js/mokodesk/moko_css/larsDesktop.css',
        '<%= dirs.src %>js/mokodesk/moko_css/rowactions.css',
    ],
    ext_css_files: [
        '<%= dirs.lib %>js/ext2/resources/css/ext-all.css',
    ],
    banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',
    // Task configuration.
    concat: {
      options: {
        banner: '<%= banner %>',
        stripBanners: true
      },
      mokodeskLib: {
          src: [
              '<%= dirs.components %>angular/angular.js',
              '<%= dirs.components %>angular-bootstrap/ui-bootstrap-tpls.js',
              '<%= dirs.components %>angular-translate/angular-translate.js',
              '<%= dirs.components %>angular-translate-loader-static-files/angular-translate-loader-static-files.js',

              '<%= dirs.components %>spin.js/dist/spin.js',

              '<%= dirs.components %>bootstrap/dist/js/bootstrap.js',
          ],
        dest: '<%= dirs.public %>js/<%= shortName %>Lib.js'
      },
      mokodesk: {
          src: [
              '<%= dirs.src %>js/common/main.js'
          ],
          dest: '<%= dirs.public %>js/<%= shortName %>.js'
      },
      mokodeskDe: {
        src: [
            '<%= ext_js_files %>',
            '<%= ux_js_files %>',
            '<%= add_js_files %>',
            '<%= menu_js_files %>',
            '<%= grid_js_files %>',
            '<%= lang_de_colloquial_js_files %>',
            '<%= moko_js_files %>'
        ],
        dest: '<%= dirs.public %>js/mokodeskDe.js'
      },
      mokodeskDe_formal: {
        src: [
            '<%= ext_js_files %>',
            '<%= ux_js_files %>',
            '<%= add_js_files %>',
            '<%= menu_js_files %>',
            '<%= grid_js_files %>',
            '<%= lang_de_formal_js_files %>',
            '<%= moko_js_files %>'
        ],
        dest: '<%= dirs.public %>js/mokodeskDe_formal.js'
      },
      mokodeskEn: {
        src: [
            '<%= ext_js_files %>',
            '<%= ux_js_files %>',
            '<%= add_js_files %>',
            '<%= menu_js_files %>',
            '<%= grid_js_files %>',
            '<%= lang_en_colloquial_js_files %>',
            '<%= moko_js_files %>'
        ],
        dest: '<%= dirs.public %>js/mokodeskEn.js'
      },
      mokodeskEn_formal: {
        src: [
            '<%= ext_js_files %>',
            '<%= ux_js_files %>',
            '<%= add_js_files %>',
            '<%= menu_js_files %>',
            '<%= grid_js_files %>',
            '<%= lang_en_formal_js_files %>',
            '<%= moko_js_files %>'
        ],
        dest: '<%= dirs.public %>js/mokodeskEn_formal.js'
      },
      mokodeskFr: {
        src: [
            '<%= ext_js_files %>',
            '<%= ux_js_files %>',
            '<%= add_js_files %>',
            '<%= menu_js_files %>',
            '<%= grid_js_files %>',
            '<%= lang_fr_js_files %>',
            '<%= moko_js_files %>'
        ],
        dest: '<%= dirs.public %>js/mokodeskFr.js'
      },
      cssLib: {
        src: [
              '<%= dirs.components %>bootstrap/dist/css/bootstrap.css'
             ],
        dest: '<%= dirs.public %>css/<%= shortName %>Lib.css'
      },
      cssExtLib: {
        src: [
              '<%= dirs.lib %>js/ext2/resources/css/ext-all.css',
              '<%= dirs.src %>js/mokodesk/moko_css/Ext.ux.grid.RowActions.css',
              '<%= dirs.src %>js/mokodesk/moko_css/filetype.css',
              '<%= dirs.src %>js/mokodesk/moko_css/rowactions.css',
             ],
        dest: '<%= dirs.public %>css/<%= shortName %>ExtLib.css'
      }
    },
    uglify: {
      options: {
        banner: '<%= banner %>'
      },
      mokodeskLib: {
        src: '<%= concat.mokodeskLib.dest %>',
        dest: '<%= dirs.public %>js/<%= shortName %>Lib.min.js'
      },
      mokodesk: {
        src: '<%= concat.mokodesk.dest %>',
        dest: '<%= dirs.public %>js/<%= shortName %>.min.js'
      },
      mokodeskDe: {
        src: '<%= concat.mokodeskDe.dest %>',
        dest: '<%= dirs.public %>js/mokodeskDe.min.js'
      },
      mokodeskDe_formal: {
        src: '<%= concat.mokodeskDe_formal.dest %>',
        dest: '<%= dirs.public %>js/mokodeskDe_formal.min.js'
      },
      mokodeskEn: {
        src: '<%= concat.mokodeskEn.dest %>',
        dest: '<%= dirs.public %>js/mokodeskEn.min.js'
      },
      mokodeskEn_formal: {
        src: '<%= concat.mokodeskEn_formal.dest %>',
        dest: '<%= dirs.public %>js/mokodeskEn_formal.min.js'
      },
      mokodeskFr: {
        src: '<%= concat.mokodeskFr.dest %>',
        dest: '<%= dirs.public %>js/mokodeskFr.min.js'
      },
    },
    cssmin: {
      csskLib: {
        src: '<%= concat.cssLib.dest %>',
        dest:'<%= dirs.public %>css/<%= shortName %>Lib.min.css'
      },
      css: {
        src: '<%= dirs.src %>css/styles.css',
        dest:'<%= dirs.public %>css/<%= shortName %>.min.css'
      },
      cssExtLib: {
        src: '<%= concat.cssExtLib.dest %>',
        dest:'<%= dirs.public %>css/<%= shortName %>ExtLib.min.css'
      },
      cssExt: {
        src: '<%= dirs.src %>js/mokodesk/moko_css/larsDesktop.css',
        dest:'<%= dirs.public %>css/<%= shortName %>Ext.min.css'
      }
    },
    jshint: {
      options: {
        curly: true,
        eqeqeq: true,
        immed: true,
        latedef: true,
        newcap: true,
        noarg: true,
        sub: true,
        undef: false,
        unused: true,
        boss: true,
        eqnull: true,
        browser: true,
        globals: {
          jQuery: true
        }
      },
      gruntfile: {
        src: 'Gruntfile.js'
      },
      /*js: {
        src: '<%= concat.js.src %>'
      }*/
    },
    watch: {
      gruntfile: {
        files: '<%= jshint.gruntfile.src %>',
        tasks: ['jshint:gruntfile']
      },
      js: {
        files: [
          //'<%= concat.js.src %>',
          '<%= ext_js_files %>',
          '<%= ux_js_files %>',
          '<%= add_js_files %>',
          '<%= menu_js_files %>',
          '<%= grid_js_files %>',
          '<%= lang_de_colloquial_js_files %>',
          '<%= lang_de_formal_js_files %>',
          '<%= lang_en_colloquial_js_files %>',
          '<%= lang_en_formal_js_files %>',
          '<%= lang_fr_js_files %>',
          '<%= moko_js_files %>'
        ],
        tasks: ['default']
      },
      css: {
        files: '<%= cssmin.css.src %>',
        tasks: ['default']
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task.
  grunt.registerTask('default', ['bower', 'jshint', 'concat', 'uglify', 'cssmin']);

};
