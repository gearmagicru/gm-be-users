/*!
 * Контроллер формы.
 * Модуль "Пользователи".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.users.FormController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-users-form',

    /**
     * @param {Ext.form.field.Field} me
     * @param {Object} newValue
     * @param {Object} oldValue
      *@param {Object} eOpts
     */
    changeGender: function (me, newValue, oldValue, eOpts) {
        if (newValue) {
            var cls, img = Ext.get('g-users__photo');
            if (img.getAttribute('data-uploaded') == 0) {
                if (me.id == 'g-users__gender-female')
                    cls = 'g-icon-svg g-icon_user-none-f';
                else
                    cls = 'g-icon-svg g-icon_user-none';
                img.set({class: cls, src: img.getAttribute('data-src')});
            }
        }
    }
});
