export class NotificationEventVariables {
  constructor() {
    this.notificationVariablesElement = $('[data-type="notification-variables"]')
    this.notificationVariablesListElement = $('[data-type="notification-variables-list"]')
    this.notificationVariablesLoaderElement = this.notificationVariablesElement.find('[data-type="loader"]')
    this.notificationEventNameElement = $('#notification_event')
    this.lastNotificationEventName = ''
  }

  init() {
    this.updateNotificationEventVariables()

    this.notificationEventNameElement
      .on('change', (event) => this.updateNotificationEventVariables(event))
  }

  updateNotificationEventVariables() {
    const eventName = this.notificationEventNameElement.val()

    if (this.lastNotificationEventName === eventName) {
      return;
    }

    this.notificationVariablesLoaderElement.addClass('active')
    this.notificationVariablesListElement.html('')

    $.ajax({
      url: `/admin/ajax/notification-event-variables?event=${eventName}`,
    }).done(data => {
      $.each(data['variables'], (index, item) => {
        this.notificationVariablesListElement.append(
          `<li><code>{${item['name']}}</code> - ${item['description']}</li>`
        )

        this.notificationVariablesLoaderElement.removeClass('active')
        this.lastNotificationEventName = eventName
      })
    })
  }
}
