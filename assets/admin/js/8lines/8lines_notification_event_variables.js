export class NotificationEventVariables {
  constructor() {
    this.notificationVariablesElement = $('[data-type="notification-variables"]')
    this.notificationVariablesListElement = $('[data-type="notification-variables-list"]')
    this.notificationVariablesLoaderElement = this.notificationVariablesElement.find('[data-type="loader"]')
    this.notificationEventCodeElement = $('[data-type="notification-event-code"]')
    this.lastNotificationEventCode = ''
  }

  init() {
    this.updateNotificationEventVariables()

    this.notificationEventCodeElement
      .on('change', (event) => this.updateNotificationEventVariables(event))
  }

  updateNotificationEventVariables() {
    const eventCode = this.notificationEventCodeElement.val()

    if (this.lastNotificationEventCode === eventCode) {
      return;
    }

    this.notificationVariablesLoaderElement.addClass('active')
    this.notificationVariablesListElement.html('')

    $.ajax({
      url: `/admin/ajax/notification-event-variables/search-all?event=${eventCode}`,
    }).done(data => {
      $.each(data['variables'], (index, item) => {
        this.notificationVariablesListElement.append(
          `<li><code>{${item['name']}}</code> - ${item['description']}</li>`
        )

        this.notificationVariablesLoaderElement.removeClass('active')
        this.lastNotificationEventCode = eventCode
      })
    })
  }
}
