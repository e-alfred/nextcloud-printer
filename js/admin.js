let elements = []

const selector = '#printer-message';

const appConfig = (name, value, callbacks) => {
    OCP.AppConfig.setValue('printer', name, value, callbacks)
}

const saveSettings = (key) => {
    const element = elements.get(key)
    let value
    let name

    if (jQuery(element).is('[data-checkbox]')) {
        name = jQuery(element).attr('data-name')
        const inputs = jQuery('input[name="' + name + '[]"]:checked')
        value = []

        inputs.each((i, v) => {
            value.push(v.value)
        })

        value = JSON.stringify(value)
    } else {
        name = jQuery(element).attr('name')
        value = jQuery(element).val()
    }

    const size = elements.length

    if (name === 'cache') {
        ++value
    }

    const callbacks = {
        success: () => {
            OC.msg.finishedSuccess(
                selector,
                t('printer', (key + 1) + '/' + size)
            )

            if (key < size - 1) {
                saveSettings(++key)
            } else {
                OC.msg.finishedSuccess(selector, t('printer', 'Saved'))
            }
        },
        error: () => {
            OC.msg.finishedError(selector, t('printer', 'Error while saving "' + element + '"'))
        }
    }

    appConfig(name, value, callbacks)
}

jQuery(document).ready(() => {
    elements = jQuery('.printer-setting')

    jQuery('#printer-save').on('click', (event) => {
        event.preventDefault()
        OC.msg.startSaving(selector)

        saveSettings(0)
    });
});
