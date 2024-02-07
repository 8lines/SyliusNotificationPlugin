export class NotificationActions {
  constructor() {
    this.notificationEventNameElement = $('[data-type="notification-event-name"]')
    this.notificationActionsElement = $('[data-type="notification-actions"]')
    this.notificationActionAddElement = this.notificationActionsElement.find('[data-form-collection="add"]')
  }

  init() {
    this.updateNotificationActionEvents()

    this.notificationEventNameElement
      .on('change', () => this.updateNotificationActionEvents())

    this.notificationActionAddElement
      .on('click', () =>
        setTimeout(() => this.updateNotificationActionEvents(), 1))
  }

  findAllNotificationActionEventElements() {
    return this.notificationActionsElement.find('[data-type="notification-action-event"]')
  }

  updateNotificationActionEvents() {
    const notificationEventName = this.notificationEventNameElement.val()

    $.each(this.findAllNotificationActionEventElements(),
      (index, notificationActionEventElement) => {
        if (notificationActionEventElement.value === notificationEventName) {
          return
        }

        notificationActionEventElement.value = notificationEventName;
        notificationActionEventElement.dispatchEvent(new Event('change', { bubbles: true }))
      })
  }
}
